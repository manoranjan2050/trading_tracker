<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Swing Trading Tracker</title>
<link rel="stylesheet" href="css/style.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
  <h1>Swing Trading Tracker</h1>

  <div class="form-container">
    <h3>Add Trade</h3>
    <form id="tradeForm">
      <input type="text" id="symbol" placeholder="Stock Symbol" required />
      <input type="number" step="0.01" id="entry_price" placeholder="Entry Price" required />
      <input type="number" step="0.01" id="stoploss" placeholder="Stoploss" required />
      <input type="number" step="0.01" id="target1" placeholder="Target 1" />
      <input type="number" step="0.01" id="target2" placeholder="Target 2" />
      <input type="date" id="date_entered" required />
      <input type="number" id="holding_period" placeholder="Holding Period (days)" />
      <button type="submit">Add Trade</button>
    </form>
  </div>

  <div class="trade-list">
    <h3>Active Trades</h3>
    <table id="tradesTable">
      <thead>
        <tr>
          <th>Symbol</th>
          <th>Entry</th>
          <th>Stoploss</th>
          <th>Target 1</th>
          <th>Target 2</th>
          <th>Date Entered</th>
          <th>Holding (days)</th>
          <th>LTP</th>
          <th>PnL</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <div class="charts">
    <h3>Total PnL Chart</h3>
    <canvas id="totalPnlChart" width="600" height="300"></canvas>
  </div>
</div>

<script src="js/script.js"></script>
</body>
</html>
