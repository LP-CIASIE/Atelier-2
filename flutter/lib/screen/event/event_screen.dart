import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class EventEditPage extends StatefulWidget {
  final Map<String, dynamic>? user;
  const EventEditPage({super.key, this.user});

  @override
  State<EventEditPage> createState() => _EventEditPageState();
}

class _EventEditPageState extends State<EventEditPage> {
  final _formKey = GlobalKey<FormState>();
  bool formPending = false;
  late final TextEditingController _firstnameController;

  @override
  void initState() {
    _firstnameController =
        TextEditingController(text: widget.user?['firstname']);
    super.initState();
  }

  Future<void> _submitForm(context) async {
    setState(() {
      formPending = true;
    });

    Map bodyHttp = {
      'firstname': _firstnameController.text,
    };

    try {
      dynamic responseHttp = await http.put(
        Uri.parse('http://gateway.atelier.local:8000/users'),
        headers: <String, String>{
          'Authorization': 'Bearer ${widget.user!['accessToken']}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(bodyHttp),
      );

      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

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

        formPending = false;
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Mon Profil'),
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
                      controller: _firstnameController,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Prénom non renseigné';
                        }
                        return null;
                      },
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                        labelText: 'Prénom',
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: ElevatedButton(
                      onPressed: formPending
                          ? null
                          : () async {
                              if (_formKey.currentState!.validate()) {
                                final BuildContext context = this.context;
                                _submitForm(context);
                              }
                            },
                      child: const Text("Enregistrer les modifications"),
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
