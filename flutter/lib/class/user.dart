class User {
  final String id;
  final String email;
  final String firstname;
  final String lastname;
  bool isCheck;

  User(
      {required this.id,
      required this.email,
      required this.firstname,
      required this.lastname,
      this.isCheck = false});

  factory User.fromMap(Map map) {
    return User(
      id: map['id'],
      email: map['email'],
      firstname: map['firstname'],
      lastname: map['lastname'],
    );
  }
}
