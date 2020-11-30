package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Year
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.Table

object YearTable : ModifyableTable<Year>("years") {
    val number = integer("number")
    val name = varchar("name", 300)
    val startDate = datetime("start_date").nullable()
    val endDate = datetime("end_date").nullable()
    val imageUri = varchar("image_uri", 45).nullable()

    override fun fromResultRow(resultRow: ResultRow) =
        Year(
            resultRow[id],
            resultRow[number],
            resultRow[name],
            resultRow[startDate],
            resultRow[endDate],
            resultRow[imageUri],
            resultRow[lastModified]
        )
}