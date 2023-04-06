import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../class/event.dart';
import '../screen/event/event_builder_screen.dart';

class EventCard extends StatelessWidget {
  const EventCard({super.key, required this.event});

  final Event event;

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 8,
      child: ListTile(
          contentPadding: const EdgeInsets.all(12),
          title: Text(event.title),
          subtitle: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 10),
              Text(
                event.description,
                maxLines: 3,
              ),
              const SizedBox(height: 10),
              Text(
                  '${DateFormat('dd/MM/yyyy').format(event.date)} à ${event.hour.hour.toString().padLeft(2, '0')}:${event.hour.minute.toString().padLeft(2, '0')}'),
            ],
          ),
          onTap: () => Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => EventBuilderPage(
                    idEvent: event.idEvent,
                  ),
                ),
              )),
    );
  }
}
