package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportFilterValue
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object ReportFilterValueTable : ModifyableTable<ReportFilterValue>("report_filter_values") {
    var reportFilterId = binary("report_filter_id", 16)
    var value = varchar("value", 100)

    override fun fromResultRow(resultRow: ResultRow) =
        ReportFilterValue(
            resultRow[id],
            resultRow[reportFilterId],
            resultRow[value],
            resultRow[lastModified]
        )

    override fun insert(obj: ReportFilterValue) =
        insert {
            it[id] = obj.id
            it[reportFilterId] = obj.reportFilterId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: ReportFilterValue) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[reportFilterId] = obj.reportFilterId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }
}