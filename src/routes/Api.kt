package com.alphadevelopmentsolutions.routes

import io.ktor.application.*
import io.ktor.response.*
import io.ktor.routing.*

interface Api {
    companion object : Route {

        override val SUB_PATH: String = "/api/"

        fun getBaseData(routing: Routing) =
            routing.get("${SUB_PATH}get/base") {
                call.respondText("THIS IS A TEST")
            }
    }
}