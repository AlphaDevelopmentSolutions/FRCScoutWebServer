package com.alphadevelopmentsolutions.routes

import io.ktor.routing.*

interface Route {
    val SUB_PATH: String
        get() = "/"

    fun createRoutes(routing: Routing)
}