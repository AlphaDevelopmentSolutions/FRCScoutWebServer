package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.DataType
import org.jetbrains.exposed.sql.ResultRow

object DataTypeTable : ModifyTrackedTable<DataType>("data_types") {
    var name = varchar("name", 16)
    var canMax = bool("can_max")
    var canMin = bool("can_min")
    var canNullZeros = bool("can_null_zeros")
    var canReport = bool("can_report")

    override fun fromResultRow(resultRow: ResultRow) =
        DataType(
            resultRow[id],
            resultRow[name],
            resultRow[canMax],
            resultRow[canMin],
            resultRow[canNullZeros],
            resultRow[canReport],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )
}