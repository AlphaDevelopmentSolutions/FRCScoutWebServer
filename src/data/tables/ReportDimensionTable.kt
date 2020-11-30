package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportDimension
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object ReportDimensionTable : ModifyableTable<ReportDimension>("report_dimensions") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)

    override fun fromResultRow(resultRow: ResultRow) =
        ReportDimension(
            resultRow[id],
            resultRow[reportId],
            resultRow[value],
            resultRow[lastModified]
        )

    override fun insert(obj: ReportDimension) =
        insert {
            it[id] = obj.id
            it[reportId] = obj.reportId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: ReportDimension) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[reportId] = obj.reportId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }
}