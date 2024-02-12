const mysql = require('mysql');

const dbConfig = {
    host: '51.161.35.133',
    user: 'remoto',
    password: 'OyarceGroup2023',
    database: 'b3'
};

let db;

function connectDatabase() {
    db = mysql.createConnection(dbConfig);

    db.connect(function(err) {
        if (err) {
            console.error('Error al conectar a la base de datos:', err);
            // Intenta conectarte nuevamente después de un período de tiempo (por ejemplo, 5 segundos)
            setTimeout(connectDatabase, 5000);
        } else {
            console.log('Conexión exitosa a la base de datos');
        }
    });

    db.on('error', function(err) {
        console.error('Error de base de datos:', err);
        if (err.code === 'PROTOCOL_PACKETS_OUT_OF_ORDER') {
            console.error('Se ha producido un error de paquetes fuera de orden. Intentando reconectar...');
            // Intentar reconexión después de un intervalo (por ejemplo, 5 segundos)
            setTimeout(connectDatabase, 5000);
        } else if (err.code === 'PROTOCOL_CONNECTION_LOST') {
            console.error('La conexión a la base de datos se ha perdido. Intentando reconectar...');
            // Intentar reconexión después de un intervalo (por ejemplo, 5 segundos)
            setTimeout(connectDatabase, 5000);
        } else {
            throw err;
        }
    });
}

// Inicializa la conexión a la base de datos
connectDatabase();

// Exporta la conexión para su uso en otros módulos
module.exports = db;
