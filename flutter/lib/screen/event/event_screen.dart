import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_edit_builder_screen.dart';

class EventPage extends StatefulWidget {
  final Event event;
  const EventPage({super.key
    required this.event,});

  @override
  State<EventPage> createState() => _EventPageState();
}

class _HomePageState extends State<EventPage> {

  @override
  State<EventPage> createState() => _EventPageState();

  openDialogShareEvent() {
    AlertDialog(
      title: const Text('Reset settings?'),
      content: const Text(
          'This will reset your device to its default factory settings.'),
      actions: [
        TextButton(
          // textColor: Color(0xFF6200EE),
          onPressed: () {},
          child: const Text('CANCEL'),
        ),
        TextButton(
          // textColor: Color(0xFF6200EE),
          onPressed: () {},
          child: const Text('ACCEPT'),
        ),
      ],
    );
  }
}

class _EventPageState extends State<EventPage> {
  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        actions: [
          IconButton(
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => EventEditBuilderPage(
                    idEvent: widget.event.idEvent,
                  ),
                ),
              );
            },
            icon: const Icon(Icons.edit_outlined),
          ),
          IconButton(
            onPressed: () {},
            icon: const Icon(Icons.delete_outlined),
          ),
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 12),
              child: Text(widget.event.title,
                  style: const TextStyle(fontSize: 24.6)),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 12),
              child: Text(
                  '${DateFormat('dd/MM/yyyy').format(widget.event.date)} à ${widget.event.hour.hour.toString().padLeft(2, '0')}:${widget.event.hour.minute.toString().padLeft(2, '0')}',
                  style: const TextStyle(fontSize: 19.6)),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 12),
              child: Text(
                widget.event.description,
              ),
            ),
            Padding(
                padding: const EdgeInsets.symmetric(vertical: 12),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Participants',
                      style: TextStyle(fontSize: 19.6),
                    ),
                    OutlinedButton.icon(
                      onPressed: () => {openDialogShareEvent()},
                      icon: const Icon(Icons.add_outlined),
                      label: const Text('AJOUTER'),
                    )
                  ],
                )),
          ],
        ),
      ),
    );
  }
}
