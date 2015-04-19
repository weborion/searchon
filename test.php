<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Example - example-guide-concepts-2-production</title>
  

  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.0-rc.0/angular.min.js"></script>
  <script src="invoice1.js"></script>
  

  
</head>
<body >
  <div ng-app="invoice1" ng-controller="InvoiceController as invoice">
  <b>Invoice:</b>
  <div>
    Quantity: <input type="number" min="0" ng-model="invoice.qty" required >
  </div>
  <div>
    Costs: <input type="number" min="0" ng-model="invoice.cost" required >
    <select ng-model="invoice.inCurr">
      <option ng-repeat="c in invoice.currencies">{{c}}</option>
    </select>
  </div>
  <div>
    <b>Total:</b>
    <span ng-repeat="c in invoice.currencies">
      {{invoice.total(c) | currency:c}}
    </span>
    <button class="btn" ng-click="invoice.pay()">Pay</button>
  </div>
</div>
</body>
</html>