import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_edit_builder_screen.dart';
import 'package:lp1_ciasie_atelier_2/widget/event_share_widget.dart';
import 'package:lp1_ciasie_atelier_2/screen/home_screen.dart';

class UserTemp {
  final String id;
  final String email;
  final String role;
  final String firstname;
  final String lastname;
  bool isCheck;

  UserTemp(
      {required this.id,
      required this.email,
      required this.role,
      required this.firstname,
      required this.lastname,
      this.isCheck = false});

  void check(value) {
    isCheck = value;
  }

  // factory userFromMap(){
  //   return UserTemp(id: id, email: email, role: role, firstname: firstname, lastname: lastname)
  // }
}

class EventPage extends StatefulWidget {
  final Event event;
  const EventPage({
    super.key,
    super.key,
    required this.event,
  });
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
        return EventShareWidget(
          idEvent: widget.event.idEvent,
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_outlined),
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
                    // onPressed: () => {
                    //   Navigator.push(
                    //     context,
                    //     MaterialPageRoute(
                    //       builder: (context) => const EventSharePage(),
                    //     ),
                    //   )
                    // },
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
