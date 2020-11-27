package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class Match(
    override var id: ByteArray,
    val eventId: ByteArray,
    val key: String,
    val typeId: ByteArray,
    val setNumber: Int,
    val matchNumber: Int,
    val blueAllianceTeamOneId: ByteArray,
    val blueAllianceTeamTwoId: ByteArray,
    val blueAllianceTeamThreeId: ByteArray,
    val redAllianceTeamOneId: ByteArray,
    val redAllianceTeamTwoId: ByteArray,
    val redAllianceTeamThreeId: ByteArray,
    val blueAllianceScore: Int,
    val redAllianceScore: Int,
    val time: DateTime,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}