package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportFilterValue
import org.jetbrains.exposed.sql.*

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

    override fun update(obj: ReportFilterValue, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[reportFilterId] = obj.reportFilterId
            it[value] = obj.value
            it[lastModified] = obj.lastModified
        }
}