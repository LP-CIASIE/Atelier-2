import 'package:flutter/material.dart';
import 'package:flutter/src/widgets/framework.dart';
import 'package:flutter/src/widgets/placeholder.dart';

class WaitingList extends StatefulWidget {
  const WaitingList({super.key});

  @override
  State<WaitingList> createState() => _WaitingListState();
}

class _WaitingListState extends State<WaitingList> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Invitations en attente'),
      ),
      body: Card(
        child: Column(
          children: [],
        ),
      ),
    );
  }
}
