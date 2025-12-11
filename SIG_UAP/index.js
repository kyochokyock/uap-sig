const express = require("express");
const app = express();

app.get("/", (req, res) => {
  res.send("Server Railway berhasil berjalan!");
});

app.listen(process.env.PORT || 3000, () => {
  console.log("Server berjalan di port " + (process.env.PORT || 3000));
});
