package com.alphadevelopmentsolutions.data.models

abstract class ByteArrayTable(
    open val id: ByteArray
) {
    abstract override fun toString(): String
}