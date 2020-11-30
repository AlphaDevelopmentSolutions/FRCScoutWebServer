package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.data.tables.YearTable
import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.sql.ResultRow
import org.joda.time.DateTime

class ChecklistItemResult(
    @Transient override var id: ByteArray,
    @SerializedName("checklist_item_id") val checklistItemId: ByteArray,
    @SerializedName("match_id") val matchId: ByteArray,
    @SerializedName("status") val status: Status?,
    @SerializedName("completed_date") val completedDate: DateTime,
    @SerializedName("completed_by_id") val completedById: ByteArray,
    @SerializedName("is_public") val isPublic: Boolean
) : ByteArrayTable(id) {
    companion object {
        enum class Status {
            COMPLETE,
            INCOMPLETE
        }
    }

    override fun toString() =
        status?.toString() ?: ""
}