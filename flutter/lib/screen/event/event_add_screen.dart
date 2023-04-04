import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/class/user.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/home_screen.dart';
import 'package:provider/provider.dart';

class EventAddPage extends StatefulWidget {
  const EventAddPage({super.key});

  @override
  State<EventAddPage> createState() => _EventAddPageState();
}

class _EventAddPageState extends State<EventAddPage> {
  final _formKey = GlobalKey<FormState>();
  bool formPending = false;
  String errorMessage = '';
  final TextEditingController _titleController = TextEditingController();
  final TextEditingController _descriptionController = TextEditingController();
  DateTime _dateController = DateTime(
    DateTime.now().year,
    DateTime.now().month,
    DateTime.now().day,
  );
  TimeOfDay _timeController = TimeOfDay.now();

  Future<void> _submitForm(context) async {
    setState(() {
      formPending = true;
      errorMessage = '';
    });

    Map bodyHttp = {
      'title': _titleController.text,
      'description': _descriptionController.text,
      'date': DateTime(
            _dateController.year,
            _dateController.month,
            _dateController.day,
            _timeController.hour,
            _timeController.minute,
          ).millisecondsSinceEpoch /
          1000,
      'is_public': 0,
    };

    try {
      User user =
          Provider.of<SessionProvider>(context, listen: false).userDataSession;
      dynamic responseHttp = await http.post(
        Uri.parse('http://gateway.atelier.local:8000/events'),
        headers: <String, String>{
          'Authorization': 'Bearer ${user.accessToken}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(bodyHttp),
      );

      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('code')) {
          setState(() {
            errorMessage = utf8.decode(response['message'].codeUnits);
            formPending = false;
          });
        } else if (response.containsKey('event')) {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const HomePage()),
            // À CHANGER pour EventPage
          );
        } else {
          setState(() {
            errorMessage =
                'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
            formPending = false;
          });
        }
      } else {
        setState(() {
          errorMessage =
              'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
          formPending = false;
        });
      }
    } catch (error) {
      setState(() {
        errorMessage =
            'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
        formPending = false;
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
      helpText: 'Select a date',
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
        // Here we take the value from the FruitMaster object that was created by
        // the App.build method, and use it to set our appbar title.
        title: const Text('Créer un évènement'),
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
                          child: const Text("Créer l'évènement"),
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
