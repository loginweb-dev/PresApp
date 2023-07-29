const { createBot, createProvider, createFlow, addKeyword, EVENTS} = require('@bot-whatsapp/bot')
const axios = require('axios')
const BaileysProvider = require('@bot-whatsapp/provider/baileys')
const MySQLAdapter = require('@bot-whatsapp/database/mysql')
require('dotenv').config({path: '../../.env'})

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
            await flowDynamic([{body: 'Prestamo #'+midata.data.id+'\nCliente: '+midata.data.cliente.nombre_completo+'\nMonto Bs: '+midata.data.cliente.monto+'\nFecha: '+midata.data.mes_inicio+'\nTipo: '+midata.data.tipo.nombre+'\nAgente: '+midata.data.user.name+'\n'+midata.data.observacion}])
        }
    })

const flujoMenu2 = addKeyword('2')
    .addAnswer('Estos son nuestros servicios: ', null, async (ctx, {flowDynamic}) => {
        cons t midata = awai t axios(process.env.APP_URL+"/api/servicios")
        if(!midata.data){
            await flowDynamic([{body: 'Sin datos, vulva a intertar desde el inicio (enviando un hola)'}])
        }else{
            for (let index = 0; index < midata.data.length; index++) {   
                await flowDynamic([{body: midata.data[index].nombre}])             
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
                await flowDynamic([{body: midata.data[index].name}])             
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
            '\n1.- Consultar mi deuda',
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


const main = async () => {
    const adapterDB = new MySQLAdapter({
        host: MYSQL_DB_HOST,
        user: MYSQL_DB_USER,
        database: MYSQL_DB_NAME,
        password: MYSQL_DB_PASSWORD,
        port: MYSQL_DB_PORT,
    })
    const adapterFlow = createFlow([flowPrincipal, flujoGracias])
    const adapterProvider = createProvider(BaileysProvider)
    createBot({
        flow: adapterFlow,
        provider: adapterProvider,
        database: adapterDB,
    })

    // adapterProvider.on('message', (ctx) => {
    //     const {from, body} = ctx
    //     adapterProvider.vendor.sendMessage(from+'@s.whatsapp.net', { text: 'Hola mundo' })
    // })
    // QRPortalWeb()
}

main()
