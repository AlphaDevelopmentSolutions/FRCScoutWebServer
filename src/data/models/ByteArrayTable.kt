package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.singletons.GsonInstance
import com.google.gson.annotations.SerializedName

abstract class ByteArrayTable(
    @SerializedName("id") open val id: ByteArray
) {
    abstract override fun toString(): String

    fun toJson(): String =
        GsonInstance.getInstance().toJson(this)
}