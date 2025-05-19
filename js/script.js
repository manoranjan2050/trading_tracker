document.addEventListener('DOMContentLoaded', () => {
  const tradeForm = document.getElementById('tradeForm');
  const tradesTableBody = document.querySelector('#tradesTable tbody');
  let totalPnlChart;

  tradeForm.addEventListener('submit', e => {
    e.preventDefault();
    addTrade();
  });

  function addTrade() {
    const data = {
      symbol: document.getElementById('symbol').value.trim(),
      entry_price: parseFloat(document.getElementById('entry_price').value),
      stoploss: parseFloat(document.getElementById('stoploss').value),
      target1: parseFloat(document.getElementById('target1').value) || null,
      target2: parseFloat(document.getElementById('target2').value) || null,
      date_entered: document.getElementById('date_entered').value,
      holding_period: parseInt(document.getElementById('holding_period').value) || null,
    };

    fetch('api/add_trade.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
      if(res.status === 'success') {
        tradeForm.reset();
        fetchTrades();
      } else {
        alert('Error adding trade');
      }
    });
  }

  function fetchTrades() {
    fetch('api/get_trades.php')
      .then(res => res.json())
      .then(trades => {
        tradesTableBody.innerHTML = '';
        if (trades.length === 0) {
          tradesTableBody.innerHTML = '<tr><td colspan="10">No active trades</td></tr>';
          updateChart([]);
          return;
        }

        fetchLivePrices(trades);
      });
  }

  function fetchLivePrices(trades) {
    fetch('api/get_live_prices.php')
      .then(res => res.json())
      .then(prices => {
        let totalPnl = 0;
        tradesTableBody.innerHTML = '';

        trades.forEach(trade => {
          let priceData = prices.find(p => p.trade_id == trade.trade_id);
          let ltp = priceData ? priceData.ltp : 0;
          let pnl = priceData ? priceData.pnl : 0;
          totalPnl += pnl;

          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${trade.symbol}</td>
            <td>${trade.entry_price}</td>
            <td>${trade.stoploss}</td>
            <td>${trade.target1 || ''}</td>
            <td>${trade.target2 || ''}</td>
            <td>${trade.date_entered}</td>
            <td>${trade.holding_period || ''}</td>
            <td>${ltp.toFixed(2)}</td>
            <td style="color:${pnl >= 0 ? 'green' : 'red'}">${pnl.toFixed(2)}</td>
            <td><button class="action-btn" onclick="closeTrade(${trade.trade_id})">Close</button></td>
          `;
          tradesTableBody.appendChild(tr);
        });

        updateChart(totalPnl);
      });
  }

  window.closeTrade = function(trade_id) {
    if(!confirm("Close this trade?")) return;
    fetch('api/close_trade.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({trade_id})
    })
    .then(res => res.json())
    .then(res => {
      if(res.status === 'closed') fetchTrades();
      else alert('Failed to close trade');
    });
  }

  function updateChart(totalPnl) {
    const ctx = document.getElementById('totalPnlChart').getContext('2d');
    if(totalPnlChart) totalPnlChart.destroy();

    totalPnlChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Total PnL'],
        datasets: [{
          label: 'PnL',
          data: [totalPnl || 0],
          backgroundColor: totalPnl >= 0 ? 'rgba(0, 128, 0, 0.6)' : 'rgba(255, 0, 0, 0.6)',
          borderWidth: 1
        }]
      },
      options: {
        scales: { y: { beginAtZero: true } }
      }
    });
  }

  fetchTrades();
});
