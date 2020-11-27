package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class MatchType(
    override var id: ByteArray,
    val key: String,
    val name: String,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}