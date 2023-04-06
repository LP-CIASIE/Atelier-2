import 'package:flutter/material.dart';

class Event {
  final String idEvent;
  final String title;
  final String description;
  final DateTime date;
  final TimeOfDay hour;
  final bool isPublic;
  bool iAmOwner;

  Event({
    required this.idEvent,
    required this.title,
    required this.description,
    required this.date,
    required this.hour,
    required this.isPublic,
    this.iAmOwner = false,
  });

  factory Event.fromMap(Map map) {
    Event event = Event(
      idEvent: map['id'],
      title: map['title'],
      description: map['description'],
      date: DateTime(
        DateTime.parse(map['date']).year,
        DateTime.parse(map['date']).month,
        DateTime.parse(map['date']).day,
      ),
      hour: TimeOfDay(
        hour: DateTime.parse(map['date']).hour,
        minute: DateTime.parse(map['date']).minute,
      ),
      isPublic: map['is_public'] == 1 ? true : false,
    );

    return event;
  }
}
