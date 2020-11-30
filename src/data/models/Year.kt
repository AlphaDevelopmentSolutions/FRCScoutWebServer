package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.data.tables.YearTable
import org.jetbrains.exposed.sql.ResultRow
import org.joda.time.DateTime

class Year(
    override var id: ByteArray,
    val number: Int,
    val name: String,
    val startDate: DateTime?,
    val endDate: DateTime?,
    val imageUri: String?,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        name
}