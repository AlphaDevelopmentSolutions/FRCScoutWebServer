package com.alphadevelopmentsolutions.serializers

import com.alphadevelopmentsolutions.singletons.DateFormatter
import com.google.gson.*
import org.joda.time.DateTime
import java.lang.reflect.Type
import java.text.ParseException
import java.text.SimpleDateFormat
import java.util.*

class DateTimeSerializer : JsonSerializer<DateTime>, JsonDeserializer<DateTime>{

    private val dateFormats = arrayOf(
        "yyyy-MM-dd HH:mm:ss",
        "yyyy-MM-dd H:mm:ss",
        "yyyy-MM-dd",
        "yyyy-MM-dd'T'HH:mm:ssZ",
        "yyyy-MM-dd'T'HH:mm:ss",
        "yyyy-MM-dd",
        "EEE MMM dd HH:mm:ss z yyyy",
        "HH:mm:ss",
        "MM/dd/yyyy HH:mm:ss aaa",
        "yyyy-MM-dd'T'HH:mm:ss.SSSSSS",
        "yyyy-MM-dd'T'HH:mm:ss.SSSSSSS",
        "yyyy-MM-dd'T'HH:mm:ss.SSSSSSS'Z'",
        "MMM d',' yyyy H:mm:ss a"
    )

    /**
     * Serializes data for usage on the api
     */
    override fun serialize(
        src: DateTime,
        typeOfSrc: Type,
        context: JsonSerializationContext
    ): JsonElement {
        return context.serialize(
            DateFormatter.getInstance().let {
                it.timeZone = TimeZone.getTimeZone("UTC")
                it.applyPattern("yyyy-MM-dd HH:mm:ss")
                val json = it.format(Date(src.millis))
                it.timeZone = TimeZone.getDefault()
                json
            }
        )
    }

    /**
     * Deserializes data from api
     */
    override fun deserialize(
        json: JsonElement,
        typeOfT: Type,
        context: JsonDeserializationContext
    ): DateTime {
        for (dateFormat in dateFormats) {
            try {
                val dateFormatter = DateFormatter.getInstance()

                dateFormatter.applyPattern(dateFormat)
                dateFormatter.timeZone = TimeZone.getTimeZone("UTC")

                dateFormatter.parse(json.asString)?.let { utcDate ->
                    dateFormatter.timeZone = TimeZone.getDefault()
                    val dateString = dateFormatter.format(utcDate)

                    dateFormatter.parse(dateString)?.let {
                        return DateTime(it.time)
                    }
                }

                throw ParseException("", 0)
            } catch (e: ParseException) { }
        }

        throw JsonParseException("Cannot parse date: ${json.asString}")
    }
}