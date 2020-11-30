package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportFilter
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object ReportFilterTable : ModifyableTable<ReportFilter>("report_filters") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)

    override fun fromResultRow(resultRow: ResultRow) =
        ReportFilter(
            resultRow[id],
            resultRow[reportId],
            resultRow[value],
            resultRow[lastModified]
        )

    override fun insert(obj: ReportFilter) =
        insert {
            it[id] = obj.id
            it[reportId] = obj.reportId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: ReportFilter) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[reportId] = obj.reportId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }
}