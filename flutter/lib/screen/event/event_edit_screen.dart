import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/class/session.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:provider/provider.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

class EventEditPage extends StatefulWidget {
  final Event event;
  const EventEditPage({super.key, required this.event});

  @override
  State<EventEditPage> createState() => _EventEditPageState();
}

class _EventEditPageState extends State<EventEditPage> {
  final _formKey = GlobalKey<FormState>();
  bool formPending = false;
  String errorMessage = '';
  late final TextEditingController _titleController;
  late final TextEditingController _descriptionController;
  late DateTime _dateController;

  late TimeOfDay _timeController;

  @override
  void initState() {
    _titleController = TextEditingController(text: widget.event.title);
    _descriptionController =
        TextEditingController(text: widget.event.description);
    _dateController = widget.event.date;
    _timeController = widget.event.hour;
    super.initState();
  }

  Future<void> _submitForm(context) async {
    setState(() {
      formPending = true;
    });

    Map bodyHttp = {
      'title': _titleController.text,
      'description': _descriptionController.text,
      'is_public': 0,
      'date': DateTime(
            _dateController.year,
            _dateController.month,
            _dateController.day,
            _timeController.hour,
            _timeController.minute,
          ).millisecondsSinceEpoch /
          1000,
    };
    try {
      Session user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;

      dynamic responseHttp = await http.put(
        Uri.parse('${dotenv.env['API_URL']}/events/${widget.event.idEvent}'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(bodyHttp),
      );

      if (!responseHttp.body.isEmpty) {
        Map response = jsonDecode(responseHttp.body);

        if (response.containsKey('code')) {
          SnackBar snackBar = SnackBar(
            content: Text(utf8.decode(response['message'].codeUnits)),
          );
          ScaffoldMessenger.of(context).showSnackBar(snackBar);
        }
        setState(() {
          formPending = false;
        });
      } else {
        setState(() {
          formPending = false;
        });

        SnackBar snackBar = const SnackBar(
          content: Text('Modifications enregistrées.'),
        );
        ScaffoldMessenger.of(context).showSnackBar(snackBar);
      }
    } catch (error) {
      setState(() {
        formPending = false;
        SnackBar snackBar = const SnackBar(
          content: Text(
              'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.'),
        );
        ScaffoldMessenger.of(context).showSnackBar(snackBar);
      });
    }
  }

  Future<void> selectDate() async {
    DateTime? dateSelected = await showDatePicker(
      context: context,
      locale: const Locale("fr", "FR"),
      initialDate: _dateController,
      firstDate: DateTime.now().subtract(const Duration(days: 365250)),
      lastDate: DateTime.now().add(const Duration(days: 365250)),
    );

    if (dateSelected != null) {
      setState(() {
        _dateController = dateSelected;
      });
    }
  }

  Future<void> selectTime() async {
    TimeOfDay? timeSelected = await showTimePicker(
      context: context,
      initialTime: _timeController,
      builder: (context, child) {
        return MediaQuery(
          data: MediaQuery.of(context).copyWith(alwaysUse24HourFormat: true),
          child: child ?? Container(),
        );
      },
    );

    if (timeSelected != null) {
      setState(() {
        _timeController = timeSelected;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Modifiez l\'évènement'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: TextFormField(
                      controller: _titleController,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Titre non renseigné.';
                        }
                        if (value.length < 3) {
                          return 'Titre trop court.';
                        }
                        return null;
                      },
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                        labelText: 'Titre',
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: TextFormField(
                      controller: _descriptionController,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Description non renseignée.';
                        }
                        if (value.length < 3) {
                          return 'Description trop courte.';
                        }
                        return null;
                      },
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                        labelText: 'Description',
                      ),
                    ),
                  ),
                  Padding(
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      child: Row(
                        children: [
                          Padding(
                            padding: const EdgeInsets.only(right: 12),
                            child: TextButton.icon(
                              onPressed: () async => {selectDate()},
                              style: const ButtonStyle(
                                alignment: Alignment.centerLeft,
                              ),
                              icon: const Icon(Icons.calendar_today_outlined),
                              label: Text(
                                DateFormat('dd/MM/yyyy')
                                    .format(_dateController),
                              ),
                            ),
                          ),
                          TextButton.icon(
                            onPressed: () async => {selectTime()},
                            style: const ButtonStyle(
                              alignment: Alignment.centerLeft,
                            ),
                            icon: const Icon(Icons.schedule_outlined),
                            label: Text(
                              '${_timeController.hour.toString().padLeft(2, '0')}:${_timeController.minute.toString().padLeft(2, '0')}',
                            ),
                          ),
                        ],
                      )),
                  Visibility(
                    visible: errorMessage != '',
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      child: Text(
                        errorMessage,
                        style: const TextStyle(color: Colors.red),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        ElevatedButton(
                          onPressed: formPending
                              ? null
                              : () async {
                                  if (_formKey.currentState!.validate()) {
                                    final BuildContext context = this.context;
                                    _submitForm(context);
                                  }
                                },
                          child: const Text("Enregistrer les modifications."),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}
