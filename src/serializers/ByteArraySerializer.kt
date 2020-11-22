package com.alphadevelopmentsolutions.serializers

import com.google.gson.*
import java.lang.reflect.Type
import java.nio.ByteBuffer
import java.util.*

class ByteArraySerializer : JsonSerializer<ByteArray>, JsonDeserializer<ByteArray>{



    /**
     * Serializes data for usage on the api
     */
    override fun serialize(
        src: ByteArray,
        typeOfSrc: Type,
        context: JsonSerializationContext
    ): JsonElement {
        return context.serialize(Base64.getEncoder().encodeToString(src))
    }

    /**
     * Deserializes data from api
     */
    override fun deserialize(
        json: JsonElement,
        typeOfT: Type,
        context: JsonDeserializationContext
    ): ByteArray {

        val byteString = json.asString

        return Base64.getDecoder().decode(byteString)
    }
}