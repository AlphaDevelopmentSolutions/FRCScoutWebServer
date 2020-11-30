package com.alphadevelopmentsolutions

import com.fasterxml.uuid.EthernetAddress
import com.fasterxml.uuid.Generators
import com.fasterxml.uuid.impl.TimeBasedGenerator

object Constants {
    val UUID_GENERATOR: TimeBasedGenerator by lazy {
        Generators.timeBasedGenerator(EthernetAddress.fromInterface())
    }
}