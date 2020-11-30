package com.alphadevelopmentsolutions.extensions

import com.google.common.net.InetAddresses
import org.mindrot.jbcrypt.BCrypt

fun String.toPassword() = BCrypt.hashpw(this, BCrypt.gensalt())

fun String.toIP(): Int? {
    return try {
        InetAddresses.coerceToInteger(
            InetAddresses.forString(this)
        )
    }
    catch (e: IllegalArgumentException) {
        null
    }
}