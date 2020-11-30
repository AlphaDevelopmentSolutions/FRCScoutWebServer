package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportMeasure
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object ReportMeasureTable : ModifyableTable<ReportMeasure>("report_measures") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)

    override fun fromResultRow(resultRow: ResultRow) =
        ReportMeasure(
            resultRow[id],
            resultRow[reportId],
            resultRow[value],
            resultRow[lastModified]
        )

    override fun insert(obj: ReportMeasure) =
        insert {
            it[id] = obj.id
            it[reportId] = obj.reportId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: ReportMeasure) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[reportId] = obj.reportId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }
}