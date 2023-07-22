const { createBot, createProvider, createFlow, addKeyword} = require('@bot-whatsapp/bot')
const axios = require('axios')
const BaileysProvider = require('@bot-whatsapp/provider/baileys')
const MySQLAdapter = require('@bot-whatsapp/database/mysql')

const MYSQL_DB_HOST = 'localhost'
const MYSQL_DB_USER = 'root'
const MYSQL_DB_PASSWORD = ''
const MYSQL_DB_NAME = 'presapp3'
const MYSQL_DB_PORT = '3306'

require('dotenv').config({path: '../../.env'})

const flujoMenu1 = addKeyword('1')
    .addAnswer('Ingresa el codigo asignado de tu prestamo', { capture: true })
    .addAnswer('Resultado de la conulta: ', null, async (ctx, {flowDynamic}) => {
        const midata = await axios(process.env.APP_URL+"/api/prestamo/"+ctx.body)
        console.log(midata.data)
        if(!midata.data){
            await flowDynamic([{body: 'Sin datos, vulva a intertar desde el inicio (enviando un hola)'}])
        }else{
            await flowDynamic([{body: 'Prestamo #'+midata.data.id+'\nCliente: '+midata.data.cliente.nombre_completo+'\nMonto Bs: '+midata.data.cliente.monto+'\nFecha: '+midata.data.mes_inicio+'\nTipo: '+midata.data.tipo.nombre+'\nAgente: '+midata.data.user.name+'\n'+midata.data.observacion}])
        }
    })


const flowPrincipal = addKeyword(['hola', 'Hola','ole', 'alo', 'buenas', 'Buenas', 'alguien', 'precios', 'precios', 'iptv'])
    .addAnswer(
        [
            '🙌 Hola bienvenido, te saluda el chatbot *LIZA*, te puedo ayudar con las opciones de:',
            '\n1.- Consultar mi deuda',
            '2.- Todos nuestros servicios',
            '3.- Chatear agente de ventas',
            '\n*envia un numero para ingresar al menu*',
        ],
        null,
        null,
        [flujoMenu1]
    )

    const flujoGracias = addKeyword(['gracias', 'muchas gracias'], )
        .addAnswer('Estamos para servirle.')

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
