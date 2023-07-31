const { createBot, createProvider, createFlow, addKeyword, EVENTS} = require('@bot-whatsapp/bot')
const express = require('express');
const axios = require('axios');
const cors = require('cors')
var md = require('markdown-it')({
    html: false,
    linkify: true,
    typographer: true
  });
const BaileysProvider = require('@bot-whatsapp/provider/baileys')
const MySQLAdapter = require('@bot-whatsapp/database/mysql')
require('dotenv').config({path: '../../.env'})
var path = require('path');

const MYSQL_DB_HOST = process.env.DB_HOST
const MYSQL_DB_USER = process.env.DB_USERNAME
const MYSQL_DB_PASSWORD = process.env.DB_PASSWORD
const MYSQL_DB_NAME = process.env.DB_DATABASE
const MYSQL_DB_PORT = process.env.DB_PORT

const flujoMenu1 = addKeyword('1')
    .addAnswer('Ingresa el codigo asignado de tu prestamo', { capture: true })
    .addAnswer('Resultado de la conulta: ', null, async (ctx, {flowDynamic}) => {
        const midata = await axios(process.env.APP_URL+"/api/prestamo/"+ctx.body)
        if(!midata.data){
            await flowDynamic([{body: 'Sin datos, vulva a intertar desde el inicio (enviando un hola)'}])
        }else{
            await flowDynamic([{body: 'Prestamo #'+midata.data.id+'\nCliente: '+midata.data.cliente.nombre_completo+'\nMonto Bs: '+midata.data.monto+'\nFecha: '+midata.data.mes_inicio+'\nTipo: '+midata.data.tipo.nombre+'\nAgente: '+midata.data.user.name+'\n'+midata.data.observacion}])
        }
    })

const flujoMenu2 = addKeyword('2')
    .addAnswer('Estos son nuestros servicios: ', null, async (ctx, {flowDynamic}) => {
        const midata = await axios(process.env.APP_URL+"/api/servicios")
        if(!midata.data){
            await flowDynamic([{body: 'Sin datos, vulva a intertar desde el inicio (enviando un hola)'}])
        }else{

            for (let index = 0; index < midata.data.length; index++) {   
                var mimarkdown = md.render(midata.data[index].detalle)
                var strippedHtml = mimarkdown.replace(/<[^>]+>/g, '')
                await flowDynamic([{body: 'Nombre: '+midata.data[index].nombre+'\n------------\n'+strippedHtml}])             
            }
        }
    })

const flujoMenu3 = addKeyword('3')
    .addAnswer('Estos son nuestros agentes: ', null, async (ctx, {flowDynamic}) => {
        const midata = await axios(process.env.APP_URL+"/api/agentes")
        if(!midata.data){
            await flowDynamic([{body: 'Sin datos, vulva a intertar desde el inicio (enviando un hola)'}])
        }else{
            for (let index = 0; index < midata.data.length; index++) {   
                await flowDynamic([{body: 'Agente: '+midata.data[index].name+'\nTelefono: '+midata.data[index].phone}])             
            }
        }
    })

// const misetting =  async () =>{
//     var midata = await axios(process.env.APP_URL+"/api/settings")
//     console.log(midata.data)
// }
const flowPrincipal = addKeyword(['hola', 'Hola','ole', 'alo', 'buenas', 'Buenas', 'alguien', 'precios', 'precios', 'iptv'])
    .addAnswer(
        [
            '🙌 Hola bienvenid@, te saluda el chatbot: '+process.env.APP_NAME+', te puedo ayudar con las opciones de:',
            '\n1.- Consultar mi prestamo',
            '2.- Todos nuestros servicios',
            '3.- Chatear agente de ventas',
            '\n*envia un numero para ingresar al menu*',
        ],
        null,
        null,
        [flujoMenu1, flujoMenu2, flujoMenu3]
    )

const flujoGracias = addKeyword(['gracias', 'muchas gracias'], )
        .addAnswer('Estamos para servirle.')

// eventos--------------------------------------------------------
// const flujoWelcome = addKeyword(EVENTS.WELCOME)
    // .addAnswer('Bienvenidos al chatbot')


const adapterDB = new MySQLAdapter({
    host: MYSQL_DB_HOST,
    user: MYSQL_DB_USER,
    database: MYSQL_DB_NAME,
    password: MYSQL_DB_PASSWORD,
    port: MYSQL_DB_PORT,
})
const adapterFlow = createFlow([flowPrincipal, flujoGracias])
const adapterProvider = createProvider(BaileysProvider)

const app = express();
app.use(cors())
app.use(express.json())


app.get('/', async (req, res) => {
    res.send('CHATBOT ESTA LISTO EN EL PUERTO:'+process.env.CB_PORT);
});

app.post('/send', async (req, res) => {
    // console.log(req.body)
    var phone = req.body.phone
    var message = req.body.message
    try {
        adapterProvider.vendor.sendMessage(phone+'@s.whatsapp.net', { text: message })

    } catch (error) {
        console.log(error)
    }
    res.send('mensaje enviado')
});


//leads
adapterProvider.on('message', async (ctx) => {
    const {from, body} = ctx
    var midata = await axios.post(process.env.APP_URL+'/api/leads', {
        phone: from,
        message: body
    })
    console.log(midata.data)
})


const main = async () => {
    createBot({
        flow: adapterFlow,
        provider: adapterProvider,
        database: adapterDB,
    })
}

main()
app.listen(process.env.CB_PORT, () => {
    console.log('CHATBOT ESTA LISTO EN EL PUERTO: '+process.env.CB_PORT);
});