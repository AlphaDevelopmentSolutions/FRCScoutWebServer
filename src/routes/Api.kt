package com.alphadevelopmentsolutions.routes

import io.ktor.application.*
import io.ktor.response.*
import io.ktor.routing.*

object Api : Route {

    override val SUB_PATH: String = "/api/"

    fun createRoutes(routing: Routing) =
        routing {
            route(SUB_PATH) {
                getData(this)
                setData(this)
            }
        }

    private fun getData(route: io.ktor.routing.Route) =
        route.get("get") {
            call.respondText("Called GET DATA")
        }

    private fun setData(route: io.ktor.routing.Route) =
        route.get("set") {
            call.respondText("Called SET DATA")
        }
}