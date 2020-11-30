package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Year
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.Table
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

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

    override fun insert(obj: Year) =
        insert {
            it[id] = obj.id
            it[number] = obj.number
            it[name] = obj.name
            it[startDate] = obj.startDate
            it[endDate] = obj.endDate
            it[imageUri] = obj.imageUri
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: Year) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[number] = obj.number
            it[name] = obj.name
            it[startDate] = obj.startDate
            it[endDate] = obj.endDate
            it[imageUri] = obj.imageUri
            it[lastModified] = obj.lastModified
        }
}