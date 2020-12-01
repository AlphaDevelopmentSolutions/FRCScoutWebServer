package com.alphadevelopmentsolutions.singletons

import com.alphadevelopmentsolutions.serializers.ByteArraySerializer
import com.alphadevelopmentsolutions.serializers.DateTimeSerializer
import com.google.gson.Gson
import com.google.gson.GsonBuilder
import org.joda.time.DateTime

object GsonInstance {
    private var INSTANCE: Gson? = null

    fun getInstance(): Gson =
        INSTANCE ?: synchronized(this) {
            val tempInstance =
                GsonBuilder()
                    .registerTypeAdapter(ByteArray::class.java, ByteArraySerializer())
                    .registerTypeAdapter(DateTime::class.java, DateTimeSerializer())
                    .serializeNulls()
                    .disableHtmlEscaping()
                    .create()

            INSTANCE = tempInstance
            tempInstance
        }
}