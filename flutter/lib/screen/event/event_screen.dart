import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_edit_builder_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/home_screen.dart';

class EventPage extends StatefulWidget {
  final Event event;
  const EventPage({
    super.key,
    required this.event,
  });

  @override
  State<EventPage> createState() => _EventPageState();
}

class _EventPageState extends State<EventPage> {
  final TextEditingController _filterController = TextEditingController();

  @override
  void initState() {
    super.initState();
  }

  _openDialogShareEvent() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: TextField(
            controller: _filterController,
            decoration: const InputDecoration(
              border: OutlineInputBorder(),
              labelText: 'Rechercher un ami',
            ),
          ),
          content: Container(
            child: ListView(
              children: [
                CheckboxListTile(
                  value: false,
                  onChanged: (o) => {},
                  title: Text('temp'),
                ),
                CheckboxListTile(
                  value: false,
                  onChanged: (o) => {},
                  title: Text('temp'),
                ),
                CheckboxListTile(
                  value: false,
                  onChanged: (o) => {},
                  title: Text('temp'),
                ),
                CheckboxListTile(
                  value: false,
                  onChanged: (o) => {},
                  title: Text('temp'),
                ),
              ],
            ),
          ),
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
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: Icon(Icons.arrow_back_outlined),
          onPressed: () => {
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => const HomePage(),
              ),
            )
          },
        ),
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
                    onPressed: () => {_openDialogShareEvent()},
                    icon: const Icon(Icons.person_add_outlined),
                    label: const Text('AJOUTER'),
                  )
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
