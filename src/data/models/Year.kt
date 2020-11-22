package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.data.tables.YearTable
import org.jetbrains.exposed.sql.ResultRow
import org.joda.time.DateTime

val id = YearTable.binary("id", 16)
val number = YearTable.integer("number")
val name = YearTable.varchar("name", 30)
val startDate = YearTable.datetime("start_date")
val endDate = YearTable.datetime("end_date")
val imageUri = YearTable.varchar("image_uri", 45)
val lastModified = YearTable.datetime("last_modified")

class Year(
    val id: ByteArray,
    val number: Int,
    val name: String,
    val startDate: DateTime,
    val endDate: DateTime,
    val imageUri: String,
    val lastModified: DateTime
) {
    companion object {
        fun fromResultRow(resultRow: ResultRow): Year =
            Year(
                resultRow[YearTable.id],
                resultRow[YearTable.number],
                resultRow[YearTable.name],
                resultRow[YearTable.startDate],
                resultRow[YearTable.endDate],
                resultRow[YearTable.imageUri],
                resultRow[YearTable.lastModified]
            )
    }
}