package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportColorSeries
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object ReportColorSeriesTable : ModifyableTable<ReportColorSeries>("report_color_series") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)

    override fun fromResultRow(resultRow: ResultRow) =
        ReportColorSeries(
            resultRow[id],
            resultRow[reportId],
            resultRow[value],
            resultRow[lastModified]
        )

    override fun insert(obj: ReportColorSeries) =
        insert {
            it[id] = obj.id
            it[reportId] = obj.reportId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: ReportColorSeries) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[reportId] = obj.reportId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }
}