const express = require("express");
const app = express();
app.get("/order", (req, res) => {
  res.send("Order received");
});
app.post("/order", (req, res) => {
  res.send("POST Order received");
});
app.listen(8080, () => {
  console.log(`server is running at http://localhost:8080`);
});
