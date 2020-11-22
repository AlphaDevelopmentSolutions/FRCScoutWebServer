package com.alphadevelopmentsolutions.singletons

import java.text.SimpleDateFormat

class DateFormatter private constructor() : SimpleDateFormat() {
    companion object {
        private var INSTANCE: DateFormatter? = null

        fun getInstance(): DateFormatter {
            return INSTANCE ?: let {
                val tempInstance = DateFormatter()

                INSTANCE = tempInstance

                tempInstance
            }
        }
    }
}