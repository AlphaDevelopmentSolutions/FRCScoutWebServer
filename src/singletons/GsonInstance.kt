package com.alphadevelopmentsolutions.singletons

import com.alphadevelopmentsolutions.serializers.ByteArraySerializer
import com.alphadevelopmentsolutions.serializers.DateTimeSerializer
import com.google.gson.Gson
import com.google.gson.GsonBuilder
import org.joda.time.DateTime

interface GsonInstance {

    companion object {
        private var INSTANCE: Gson? = null

        fun getInstance(): Gson =
            INSTANCE ?: synchronized(this) {
                val tempInstance =
                    GsonBuilder()
                        .registerTypeAdapter(ByteArray::class.java, ByteArraySerializer())
                        .registerTypeAdapter(DateTime::class.java, DateTimeSerializer())
                        .create()

                INSTANCE = tempInstance
                tempInstance
            }
    }

}