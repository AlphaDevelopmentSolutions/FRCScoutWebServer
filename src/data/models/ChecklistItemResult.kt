package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.data.tables.YearTable
import org.jetbrains.exposed.sql.ResultRow
import org.joda.time.DateTime

class ChecklistItemResult(
    override var id: ByteArray,
    val checklistItemId: ByteArray,
    val matchId: ByteArray,
    val status: Status?,
    val completedDate: DateTime,
    val completedById: ByteArray,
    val isPublic: Boolean
) : ByteArrayTable(id) {
    companion object {
        enum class Status {

        }
    }

    override fun toString(): String {
        TODO("Not yet implemented")
    }
}