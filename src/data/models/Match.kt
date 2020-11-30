package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Match(
    @Transient override var id: ByteArray,
    @SerializedName("event_id") val eventId: ByteArray,
    @SerializedName("key") val key: String,
    @SerializedName("type_id") val typeId: ByteArray,
    @SerializedName("set_number") val setNumber: Int,
    @SerializedName("match_number") val matchNumber: Int,
    @SerializedName("blue_alliance_team_one_id") val blueAllianceTeamOneId: ByteArray,
    @SerializedName("blue_alliance_team_two_id") val blueAllianceTeamTwoId: ByteArray,
    @SerializedName("blue_alliance_team_three_id") val blueAllianceTeamThreeId: ByteArray,
    @SerializedName("red_alliance_team_one_id") val redAllianceTeamOneId: ByteArray,
    @SerializedName("red_alliance_team_two_id") val redAllianceTeamTwoId: ByteArray,
    @SerializedName("red_alliance_team_three_id") val redAllianceTeamThreeId: ByteArray,
    @SerializedName("blue_alliance_score") val blueAllianceScore: Int?,
    @SerializedName("red_alliance_score") val redAllianceScore: Int?,
    @SerializedName("time") val time: DateTime?,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        key
}