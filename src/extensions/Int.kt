package com.alphadevelopmentsolutions.extensions

import com.google.common.net.InetAddresses


fun Int.toIP(): String = InetAddresses.fromInteger(this).toString()