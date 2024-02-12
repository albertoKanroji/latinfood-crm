const express=require('express');
const app= express();
const http= require('http');
const server=http.createServer(app);
const logger = require('morgan');
const cors = require('cors');
const passport = require('passport');

//rutas

const usersRoutes=require('./routes/userRoutes');
const adminRoutes=require('./routes/AdminRoutes');
const port = process.env.PORT || 6501;
app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({
    extended:true
}
    
));
app.use(cors());
app.use(passport.initialize());
app.use(passport.session());

require('./config/passport')(passport);

app.disable('x-powered-by');


app.set('port', port);
//lamando rutas
usersRoutes(app);
adminRoutes(app);
server.listen(6501, '51.161.35.133' || 'localhost',function() {
    console.log('Server listening on port', port);
});

app.get('/',(req,res)=>{
res.send('ruta raiz del backend');
});


//errors

app.use((err,req,res,next)=>{
console.log(err);
res.status(err.status || 500).send(err.stacl);
});