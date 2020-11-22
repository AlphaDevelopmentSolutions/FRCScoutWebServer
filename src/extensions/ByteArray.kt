package com.alphadevelopmentsolutions.extensions

import java.nio.ByteBuffer
import java.util.*

/**
 * Converts a [ByteArray] object to a new [UUID] object
 * @return [UUID] object converted from [ByteArray]
 */
fun ByteArray.toUUID(): UUID =
    ByteBuffer.wrap(this).let {
        return UUID(it.long, it.long)
    }