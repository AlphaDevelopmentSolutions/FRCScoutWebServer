package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.data.tables.YearTable
import org.jetbrains.exposed.sql.ResultRow
import org.joda.time.DateTime

class TeamInvitation(
    val id: ByteArray,
    val number: Int,
    val name: String,
    val startDate: DateTime?,
    val endDate: DateTime?,
    val imageUri: String?,
    val lastModified: DateTime
) {
    companion object {
        fun fromResultRow(resultRow: ResultRow): TeamInvitation =
            TeamInvitation(
                resultRow[YearTable.id],
                resultRow[YearTable.number],
                resultRow[YearTable.name],
                resultRow[YearTable.startDate],
                resultRow[YearTable.endDate],
                resultRow[YearTable.imageUri],
                resultRow[YearTable.lastModified]
            )
    }

    enum class State {

    }
}