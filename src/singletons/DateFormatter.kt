package com.alphadevelopmentsolutions.singletons

import java.text.SimpleDateFormat

object DateFormatter {
    private var INSTANCE: SimpleDateFormat? = null

    fun getInstance(): SimpleDateFormat {
        return INSTANCE ?: let {
            val tempInstance = SimpleDateFormat()

            INSTANCE = tempInstance

            tempInstance
        }
    }
}