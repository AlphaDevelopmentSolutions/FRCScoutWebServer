package com.alphadevelopmentsolutions.extensions

import java.nio.ByteBuffer
import java.util.*

/**
 * Converts a [UUID] object to a new [ByteArray] object
 * @return [ByteArray] object converted from [UUID]
 */
fun UUID.toByteArray(): ByteArray =
    ByteBuffer.wrap(ByteArray(16)).let {

        it.putLong(this.mostSignificantBits)
        it.putLong(this.leastSignificantBits)

        return it.array()
    }