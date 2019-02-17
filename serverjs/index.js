var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var bodyParser = require('body-parser');

http.listen(8989, function(){
  console.log('listening on *:8989');
});

app.get('/', function(req, res){
  var patient = req.param('patient');
//  var token = req.param('token');
//  var geo = req.param('geo');
//
  res.send('Hello from node');
  io.emit('chat message', "Sending this back from server " + patient);
});

//
//app.use(bodyParser.json()); // support json encoded bodies
//app.use(bodyParser.urlencoded({ extended: true })); // support encoded bodies
//
//
//app.post('/', function (req, res) {
//  //var patient = req.param('patient');
//
//  res.send('Hello from node');
//  io.emit('patientCheckin', "Sending this back from server ");
//})


app.get('/push', function(req, res) {

  //var user_id = req.param('id');
  //var token = req.param('token');
  //var geo = req.param('geo');

  io.emit('patientCheckin', req.param('event'));
  res.send("");

});
//
//app.post('/', function(req, res) {
//  var user_id = req.body.id;
//  var token = req.body.token;
//  var geo = req.body.geo;
//
//  res.send(user_id + ' ' + token + ' ' + geo);
//  io.emit('chat message', "Sending this back from server");
//});

//app.get('/', function(req, res){
//  res.send('Hello from node');
//  io.emit('chat message', "Sending this back from server");
//});
//
////Get from client
//io.on('connection', function(socket){
//  //Send message that the user is connected to the socket
//  console.log('a user connected');
//  //Listen to the event chat message
//  socket.on('chat message', function(msg){
//    console.log("Just got this from client: " + msg);
//    //Send message back to clients
//    io.emit('chat message', "Sending this back from server" + msg);
//  });
//});
//
//
//
//
//http.listen(3000, function(){
//  console.log('listening on *:3000');
//});
//
////////
//
//http.createServer(function (request, response) {
//  if(request.method === "GET") {
//    if (request.url === "/favicon.ico") {
//      response.writeHead(404, {'Content-Type': 'text/html'});
//      response.write('<!doctype html><html><head><title>404</title></head><body>404: Resource Not Found</body></html>');
//      response.end();
//    } else {
//      response.writeHead(200, {'Content-Type': 'text/html'});
//      response.end(formOutput);
//    }
//  } else if(request.method === "POST") {
//    if (request.url === "/inbound") {
//      var requestBody = '';
//      request.on('data', function(data) {
//        requestBody += data;
//        if(requestBody.length > 1e7) {
//          response.writeHead(413, 'Request Entity Too Large', {'Content-Type': 'text/html'});
//          response.end('<!doctype html><html><head><title>413</title></head><body>413: Request Entity Too Large</body></html>');
//        }
//      });
//      request.on('end', function() {
//        var formData = qs.parse(requestBody);
//        response.writeHead(200, {'Content-Type': 'text/html'});
//        response.write('<!doctype html><html><head><title>response</title></head><body>');
//        response.write('Thanks for the data!<br />User Name: '+formData.UserName);
//        response.write('<br />Repository Name: '+formData.Repository);
//        response.write('<br />Branch: '+formData.Branch);
//        response.end('</body></html>');
//      });
//    } else {
//      response.writeHead(404, 'Resource Not Found', {'Content-Type': 'text/html'});
//      response.end('<!doctype html><html><head><title>404</title></head><body>404: Resource Not Found</body></html>');
//    }
//  } else {
//    response.writeHead(405, 'Method Not Supported', {'Content-Type': 'text/html'});
//    return response.end('<!doctype html><html><head><title>405</title></head><body>405: Method Not Supported</body></html>');
//  }
//}).listen(serverPort);
//console.log('Server running at localhost:'+serverPort);
