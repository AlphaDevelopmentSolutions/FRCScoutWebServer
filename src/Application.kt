package com.alphadevelopmentsolutions

import com.alphadevelopmentsolutions.data.models.*
import com.alphadevelopmentsolutions.data.tables.*
import com.alphadevelopmentsolutions.extensions.toByteArray
import com.alphadevelopmentsolutions.extensions.toIP
import com.alphadevelopmentsolutions.extensions.toPassword
import com.alphadevelopmentsolutions.extensions.toUUID
import com.alphadevelopmentsolutions.routes.Api
import com.alphadevelopmentsolutions.singletons.GsonInstance
import com.zaxxer.hikari.HikariConfig
import com.zaxxer.hikari.HikariDataSource
import io.ktor.application.*
import io.ktor.auth.*
import io.ktor.client.*
import io.ktor.client.engine.apache.*
import io.ktor.client.features.*
import io.ktor.client.features.auth.*
import io.ktor.client.features.json.*
import io.ktor.client.features.logging.*
import io.ktor.features.*
import io.ktor.gson.*
import io.ktor.html.*
import io.ktor.http.*
import io.ktor.http.content.*
import io.ktor.response.*
import io.ktor.routing.*
import kotlinx.coroutines.runBlocking
import kotlinx.css.*
import kotlinx.html.*
import org.jetbrains.exposed.sql.Database
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.transactions.transaction
import org.joda.time.DateTime
import org.mindrot.jbcrypt.BCrypt
import java.util.*

fun main(args: Array<String>): Unit {

    val config =
            HikariConfig().apply {
                jdbcUrl = "jdbc:mysql://localhost/app"
                driverClassName = "com.mysql.cj.jdbc.Driver"
                username = "testuser"
                password = "password"
                maximumPoolSize = 10
            }

    val dataSource = HikariDataSource(config)
    Database.connect(dataSource)

    return io.ktor.server.netty.EngineMain.main(args)
}

@Suppress("unused") // Referenced in application.conf
@kotlin.jvm.JvmOverloads
fun Application.module(testing: Boolean = false) {
    install(Compression) {
        gzip {
            priority = 1.0
        }
        deflate {
            priority = 10.0
            minimumSize(1024) // condition
        }
    }

    install(DataConversion)

    // https://ktor.io/servers/features/https-redirect.html#testing
    if (!testing && false) {
        install(HttpsRedirect) {
            // The port to redirect to. By default 443, the default HTTPS port.
            sslPort = 443
            // 301 Moved Permanently, or 302 Found redirect.
            permanentRedirect = true
        }
    }

    install(Authentication) {
        basic("myBasicAuth") {
            realm = "Ktor Server"
            validate { if (it.name == "test" && it.password == "password") UserIdPrincipal(it.name) else null }
        }
    }

    install(ContentNegotiation) {
        gson {

        }
    }

    val client = HttpClient(Apache) {
        install(HttpTimeout) {
        }
        install(Auth) {
        }
        install(JsonFeature) {
            serializer = GsonSerializer()
        }
        install(Logging) {
            level = LogLevel.HEADERS
        }
        BrowserUserAgent() // install default browser-like user-agent
        // install(UserAgent) { agent = "some user agent" }
    }
    runBlocking {
        // Sample for making a HTTP Client request
        /*
        val message = client.post<JsonSampleClass> {
            url("http://127.0.0.1:8080/path/to/endpoint")
            contentType(ContentType.Application.Json)
            body = JsonSampleClass(hello = "world")
        }
        */
    }

    routing {
        Api.createRoutes(this)

        get("/test") {

            val newUser =
                User(
                    UUID.fromString("593e565a-32bc-11eb-b2e1-5c80b67a2786").toByteArray(),
                    "Griffin",
                    "Sorrentino",
                    "griffinsorrentino@gmail.com",
                    "griffinsorrentino",
                    "test description",
                    "testuri",
                    null,
                    null,
                    DateTime(),
                    ByteArray(0)
                ).apply {
                    password = "testingpassword123".toPassword()
                }

            val authToken =
                AuthToken(
                    Constants.UUID_GENERATOR.generate().toByteArray(),
                    newUser.id,
                    "127.0.0.1".toIP() ?: 0,
                    DateTime(System.currentTimeMillis())
                )

            val team =
                Team(
                    Constants.UUID_GENERATOR.generate().toByteArray(),
                    5885,
                    "Villanova Wiredcats",
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    DateTime()
                )

            val teamAccount =
                TeamAccount(
                    Constants.UUID_GENERATOR.generate().toByteArray(),
                    team.id,
                    "Villanova Team Account",
                    null,
                    "wiredcats",
                    newUser.id,
                    null,
                    null,
                    null,
                    DateTime(),
                    null,
                    null,
                    DateTime(),
                    newUser.id
                )

            val userTeamAccountList =
                UserTeamAccountList(
                    Constants.UUID_GENERATOR.generate().toByteArray(),
                    newUser.id,
                    teamAccount.id,
                    UserTeamAccountList.Companion.State.ENABLED,
                    null,
                    null,
                    DateTime(),
                    newUser.id
                )

            transaction {
                exec("SET FOREIGN_KEY_CHECKS=0;")
            }

            transaction {
//                UserTable.upsert(newUser)
//                AuthTokenTable.upsert(authToken)
//                TeamTable.upsert(team)
//                TeamAccountTable.upsert(teamAccount)
//                UserTeamAccountListTable.upsert(userTeamAccountList)
            }

            call.respondText(newUser.toJson(), ContentType.Application.Json)
        }

        get("/html-dsl") {
            call.respondHtml {
                head {
                    link(rel = "stylesheet", href = "/styles.css")
                    script {
                        src = "https://code.jquery.com/jquery-3.5.1.min.js"
                    }
                }

                body {
                    h1 { +"HTML" }
                    ul {
                        for (n in 1..10) {
                            li { +"$n" }
                        }
                    }
                }
            }
        }

        get("/styles.css") {
            call.respondCss {
                body {
//                    backgroundColor = Color.red
                }

                p {
                    fontSize = 2.em
                }

                rule("p.myclass") {
                    color = Color.blue
                }
            }
        }

        // Static feature. Try to access `/static/ktor_logo.svg`
        static("/static") {
            resources("static")
        }

        authenticate("myBasicAuth") {
            get("/protected/route/basic") {
                val principal = call.principal<UserIdPrincipal>()!!
                call.respondText("Hello ${principal.name}")
            }
        }

        get("/json/gson") {
            call.respond(mapOf("hello" to "world"))
        }
    }
}

data class JsonSampleClass(val hello: String)

fun FlowOrMetaDataContent.styleCss(builder: CSSBuilder.() -> Unit) {
    style(type = ContentType.Text.CSS.toString()) {
        +CSSBuilder().apply(builder).toString()
    }
}

fun CommonAttributeGroupFacade.style(builder: CSSBuilder.() -> Unit) {
    this.style = CSSBuilder().apply(builder).toString().trim()
}

suspend inline fun ApplicationCall.respondCss(builder: CSSBuilder.() -> Unit) {
    this.respondText(CSSBuilder().apply(builder).toString(), ContentType.Text.CSS)
}
