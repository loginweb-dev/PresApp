const { createBot, createProvider, createFlow, addKeyword} = require('@bot-whatsapp/bot')
// require('dotenv').config()
const axios = require('axios')
// const QRPortalWeb = require('@bot-whatsapp/portal')
const BaileysProvider = require('@bot-whatsapp/provider/baileys')
const MySQLAdapter = require('@bot-whatsapp/database/mysql')
// const { add } = require('lodash')

/**
 * Declaramos las conexiones de MySQL
 */
const MYSQL_DB_HOST = 'localhost'
const MYSQL_DB_USER = 'root'
const MYSQL_DB_PASSWORD = ''
const MYSQL_DB_NAME = 'presapp3'
const MYSQL_DB_PORT = '3306'

/**
 * Aqui declaramos los flujos hijos, los flujos se declaran de atras para adelante, es decir que si tienes un flujo de este tipo:
 *
 *          Menu Principal
 *           - SubMenu 1
 *             - Submenu 1.1
 *           - Submenu 2
 *             - Submenu 2.1
 *
 * Primero declaras los submenus 1.1 y 2.1, luego el 1 y 2 y al final el principal.
 */

// const flowSecundario = addKeyword(['2', 'siguiente']).addAnswer(['📄 Aquí tenemos el flujo secundario'])

// const flowDocs = addKeyword(['doc', 'documentacion', 'documentación']).addAnswer(
//     [
//         '📄 Aquí encontras las documentación recuerda que puedes mejorarla',
//         'https://bot-whatsapp.netlify.app/',
//         '\n*2* Para siguiente paso.',
//     ],
//     null,
//     null,
//     [flowSecundario]
// )

// const flowTuto = addKeyword(['tutorial', 'tuto']).addAnswer(
//     [
//         '🙌 Aquí encontras un ejemplo rapido',
//         'https://bot-whatsapp.netlify.app/docs/example/',
//         '\n*2* Para siguiente paso.',
//     ],
//     null,
//     null,
//     [flowSecundario]
// )

// const flowGracias = addKeyword(['gracias', 'grac']).addAnswer(
//     [
//         '🚀 Puedes aportar tu granito de arena a este proyecto',
//         '[*opencollective*] https://opencollective.com/bot-whatsapp',
//         '[*buymeacoffee*] https://www.buymeacoffee.com/leifermendez',
//         '[*patreon*] https://www.patreon.com/leifermendez',
//         '\n*2* Para siguiente paso.',
//     ],
//     null,
//     null,
//     [flowSecundario]
// )

// const flowDiscord = addKeyword(['discord']).addAnswer(
//     ['🤪 Únete al discord', 'https://link.codigoencasa.com/DISCORD', '\n*2* Para siguiente paso.'],
//     null,
//     null,
//     [flowSecundario]
// )

// class leadsClass extends CoreClass {
    // const handleMsg = async (ctx) => {
    //     const {from, body} = ctx
    //     console.log(body)
    //     this.sendFlowSimple(['hola mundo'], from)
    // }

// }

const flujoMenu1 = addKeyword('1')
    .addAnswer('Ingresa el codigo asignado de tu prestamo', { capture: true })
    .addAnswer('Resultado de la conulta: ', null, async (ctx, {flowDynamic}) => {
        const midata = await axios("http://localhost:8000/api/prestamo/"+ctx.body)
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

    adapterProvider.on('message', (ctx) => {
        const {from, body} = ctx
        adapterProvider.vendor.sendMessage(from+'@s.whatsapp.net', { text: 'Hola mundo' })
    })
    // QRPortalWeb()
}

main()
